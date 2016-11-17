/****************************
     Canny edge detection
*****************************/

#include <iostream>
#include <bits/stdc++.h>
#include "opencv2/core/core.hpp"
#include "opencv2/highgui/highgui.hpp"
#include "opencv2/opencv.hpp"
#include "opencv2/imgproc/imgproc.hpp"
#include <opencv2/objdetect/objdetect.hpp>
#include <math.h>

using namespace cv; 
using namespace std; 

int main()
{
    // Reading image
    Mat img = imread("pf.jpg");

    // Displaying image
    //imshow("Original Image",img);
    //waitKey(0);

    // Converting to grayscale
    Mat img_gray,image_gray;
    cvtColor(img,image_gray,CV_RGB2GRAY);
    GaussianBlur( image_gray, img_gray, Size(3,3), 3, 3);

    
    // Displaying grayscale image
    imshow("Original Image",img_gray);
    waitKey(0);

    
    int cols = img_gray.cols;
    int rows = img_gray.rows;

    // Creating sobel operator in x direction
    int sobel_x[3][3] = {-1,0,1,-2,0,2,-1,0,1};
    // Creating sobel operator in y direction
    int sobel_y[3][3] = {-1,-2,-1,0,0,0,1,2,1};


    int radius = 1;
    
    // Handle border issues
    Mat _src;
    copyMakeBorder(img_gray, _src, radius, radius, radius, radius, BORDER_REFLECT101);

    // Create output matrix
    Mat gradient_x = img_gray.clone();
    Mat gradient_y = img_gray.clone();
    Mat gradient_f = img_gray.clone();
    Mat gradient_mag = img_gray.clone();

    // Conrrelation loop in x direction 
    
    // Iterate on image 
    for (int r = radius; r < _src.rows - radius; ++r)
    {
        for (int c = radius; c < _src.cols - radius; ++c)
        {
            int s = 0;

            // Iterate on kernel
            for (int i = -radius; i <= radius; ++i)
            {
                for (int j = -radius; j <= radius; ++j)
                {
                    s += _src.at<uchar>(r + i, c + j) * sobel_x[i + radius][j + radius];
                }
            }
            gradient_x.at<uchar>(r - radius, c - radius) = s/100;

            /*if(s>200)
                gradient.at<uchar>(r - radius, c - radius) = 255;
            else
                gradient.at<uchar>(r - radius, c - radius) = 0;
            */    
        }
    }

    // Conrrelation loop in y direction 
    
    // Iterate on image 
    for (int r = radius; r < _src.rows - radius; ++r)
    {
        for (int c = radius; c < _src.cols - radius; ++c)
        {
            int s = 0;

            // Iterate on kernel
            for (int i = -radius; i <= radius; ++i)
            {
                for (int j = -radius; j <= radius; ++j)
                {
                    s += _src.at<uchar>(r + i, c + j) * sobel_y[i + radius][j + radius];
                }
            }
            gradient_y.at<uchar>(r - radius, c - radius) = s/100;

            /*if(s>200)
                gradient.at<uchar>(r - radius, c - radius) = 255;
            else
                gradient.at<uchar>(r - radius, c - radius) = 0;
            */    
        }
    }

    ///cout<<endl<<"max:"<<max;
    //cout<<img_gray.rows;
    //cout<<endl<<_src.rows;
    cout<<endl<<gradient_x.rows;
    cout<<endl<<gradient_y.rows;
    cout<<endl<<gradient_f.rows<<gradient_f.cols;   
    

    //Calculating gradient magnitude
    for(int i=0; i<gradient_mag.rows; i++)
    {
        for(int j=0; j<gradient_mag.cols; j++)
        {
            gradient_mag.at<uchar>(i,j) = sqrt( pow(gradient_x.at<uchar>(i,j),2) + pow(gradient_y.at<uchar>(i,j),2) );  
        
             if(gradient_mag.at<uchar>(i,j) >252)
                gradient_f.at<uchar>(i,j) = 255;
            else
                gradient_f.at<uchar>(i,j) = 0;
        }
    }
    

    /*
    imshow("grad x",gradient_x);
    waitKey(0);

    imshow("grad y",gradient_y);
    waitKey(0);
    */

    imshow("grad magnitude",gradient_f);
    waitKey(0); 
    
    int max=0;


    // Performing Non-Maximum Surpression
    float theta; // Calculate intensity gradient vector theta=atan2(Gy,Gx);
    Mat nonMaxSupp= Mat(gradient_mag.rows-2, gradient_mag.cols-2, CV_8UC1); //CV_8UC1 is 8-bit single channel image i.e grayscale
    
    for(int i=1; i<gradient_x.rows-1; i++)
    {
        for(int j=1; j<gradient_x.cols-1; j++)
        {
            //if(gradient_x.at<uchar>(i,j) ==0) //Arctan Fix
             //   theta = 90;
            //else
                theta = atan2(gradient_y.at<uchar>(i,j),gradient_x.at<uchar>(i,j))*(180/3.14);
                //theta = atan(gradient_y.at<uchar>(i,j)/gradient_x.at<uchar>(i,j))*(180/3.14);
            //cout<<theta<<endl;
            //if(theta>max)
            //    max=theta;
            nonMaxSupp.at<uchar>(i-1, j-1) = gradient_mag.at<uchar>(i,j);
            
            // For horizontal edge
            if(((-22.5 < theta) && (theta <= 22.5)) || ((157.5 < theta) && (theta <= -157.5)))
            {
                if ((gradient_mag.at<uchar>(i,j) < gradient_mag.at<uchar>(i,j+1)) || (gradient_mag.at<uchar>(i,j) < gradient_mag.at<uchar>(i,j-1)))
                    nonMaxSupp.at<uchar>(i-1, j-1) = 0;
            }

            //For vertical edge
            if (((-112.5 < theta) && (theta <= -67.5)) || ((67.5 < theta) && (theta <= 112.5)))
            {
                if ((gradient_mag.at<uchar>(i,j) < gradient_mag.at<uchar>(i+1,j)) || (gradient_mag.at<uchar>(i,j) < gradient_mag.at<uchar>(i-1,j)))
                    nonMaxSupp.at<uchar>(i-1, j-1) = 0;
            }

            // For 135 degree or -45 degree edge
            if (((-67.5 < theta) && (theta <= -22.5)) || ((112.5 < theta) && (theta <= 157.5)))
            {
                if ((gradient_mag.at<uchar>(i,j) < gradient_mag.at<uchar>(i-1,j+1)) || (gradient_mag.at<uchar>(i,j) < gradient_mag.at<uchar>(i+1,j-1)))
                    nonMaxSupp.at<uchar>(i-1, j-1) = 0;
            }

            // For 45 Degree Edge
            if (((-157.5 < theta) && (theta <= -112.5)) || ((22.5 < theta) && (theta <= 67.5)))
            {
                if ((gradient_mag.at<uchar>(i,j) < gradient_mag.at<uchar>(i+1,j+1)) || (gradient_mag.at<uchar>(i,j) < gradient_mag.at<uchar>(i-1,j-1)))
                    nonMaxSupp.at<uchar>(i-1, j-1) = 0;
            }

        }

    }
    //cout<<endl<<"max"<<max;
    imshow("Non-Maximum Surpression",nonMaxSupp);
    waitKey(0);

    //Hysterisis Threshold
    int low=50,high=90;

    if(low > 255)
        low = 255;
    if(high > 255)
        high = 255;
    
    Mat EdgeMat = Mat(nonMaxSupp.rows, nonMaxSupp.cols, nonMaxSupp.type());
    
    for (int i=0; i<nonMaxSupp.rows; i++) 
    {
        for (int j = 0; j<nonMaxSupp.cols; j++) 
        {
            EdgeMat.at<uchar>(i,j) = nonMaxSupp.at<uchar>(i,j);
            if(EdgeMat.at<uchar>(i,j) > high)
                EdgeMat.at<uchar>(i,j) = 255;
            else if(EdgeMat.at<uchar>(i,j) < low)
                EdgeMat.at<uchar>(i,j) = 0;
            else
            {
                bool anyHigh = false;
                bool anyBetween = false;
                for (int x=i-1; x < i+2; x++) 
                {
                    for (int y = j-1; y<j+2; y++) 
                    {
                        if(x <= 0 || y <= 0 || x > EdgeMat.rows || y > EdgeMat.cols) //Out of bounds
                            continue;
                        else
                        {
                            if(EdgeMat.at<uchar>(x,y) > high)
                            {
                                EdgeMat.at<uchar>(i,j) = 255;
                                anyHigh = true;
                                break;
                            }
                            else if(EdgeMat.at<uchar>(x,y) <= high && EdgeMat.at<uchar>(x,y) >= low)
                                anyBetween = true;
                        }
                    }
                    if(anyHigh)
                        break;
                }
                if(!anyHigh && anyBetween)
                    for (int x=i-2; x < i+3; x++) 
                    {
                        for (int y = j-1; y<j+3; y++) 
                        {
                            if(x < 0 || y < 0 || x > EdgeMat.rows || y > EdgeMat.cols) //Out of bounds
                                continue;
                            else
                            {
                                if(EdgeMat.at<uchar>(x,y) > high)
                                {
                                    EdgeMat.at<uchar>(i,j) = 255;
                                    anyHigh = true;
                                    break;
                                }
                            }
                        }
                        if(anyHigh)
                            break;
                    }
                if(!anyHigh)
                    EdgeMat.at<uchar>(i,j) = 0;
            }
        }
    }
    
    imshow("Hysterisis Thresholding",EdgeMat);
    waitKey(0);

    return 0;

}